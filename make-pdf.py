import pandas as pd
from PIL import Image, ImageDraw, ImageFont
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4, landscape
from reportlab.lib.utils import ImageReader
import io
import os
import re

def read_csv_texts(csv_path):
    df = pd.read_csv(csv_path)
    return df['text'].tolist()

def sanitize_filename(text):
    # แทนที่อักขระที่ห้ามใช้ในชื่อไฟล์ด้วย "_" และตัดความยาวไม่เกิน 50 ตัวอักษร
    safe_text = re.sub(r'[\\/*?:"<>|]', "_", text)
    return safe_text.strip()[:50]

def draw_text_centered(base_img_path, text, font_path, font_size=30, y_pos=100):
    img = Image.open(base_img_path).convert("RGB")
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(font_path, font_size)

    image_width = img.width
    bbox = draw.textbbox((0, 0), text, font=font)
    text_width = bbox[2] - bbox[0]
    x = (image_width - text_width) // 2

    draw.text((x, y_pos), text, font=font, fill=(0, 0, 0))
    return img

def save_image_as_pdf(img, pdf_path):
    img_byte_arr = io.BytesIO()
    img.save(img_byte_arr, format='PNG')
    img_byte_arr.seek(0)

    c = canvas.Canvas(pdf_path, pagesize=landscape(A4))
    width, height = landscape(A4)

    img_reader = ImageReader(img_byte_arr)

    # คำนวณขนาดภาพให้ไม่บิดเบี้ยว
    img_width, img_height = img.size
    scale = min(width / img_width, height / img_height)
    new_width = img_width * scale
    new_height = img_height * scale

    x = (width - new_width) / 2
    y = (height - new_height) / 2

    c.drawImage(img_reader, x, y, width=new_width, height=new_height)
    c.showPage()
    c.save()

if __name__ == "__main__":
    csv_path = "texts.csv"
    base_img_path = "base_image.jpg"
    font_path = "JS-Wansika-Italic.ttf"

    texts = read_csv_texts(csv_path)

    output_folder = "outputs"
    os.makedirs(output_folder, exist_ok=True)

    for text in texts:
        if not isinstance(text, str) or not text.strip():
            continue  # ข้ามข้อความว่าง

        img = draw_text_centered(base_img_path, text, font_path, font_size=60, y_pos=620)

        safe_filename = sanitize_filename(text)
        pdf_path = os.path.join(output_folder, f"{safe_filename}.pdf")
        save_image_as_pdf(img, pdf_path)

        print(f"✅ สร้างไฟล์: {pdf_path}")
