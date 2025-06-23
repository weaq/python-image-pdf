<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ค้นหาใบประกาศ</title>
    <style>
        body {
            font-family: "Sarabun", sans-serif;
            background-color: #f2f2f2;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .search-container {
            max-width: 500px;
            margin: 20px auto;
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 16px;
        }

        ul#suggestions {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul#suggestions li {
            margin-bottom: 10px;
            padding: 10px 14px;
            background-color: #fff;
            border-left: 4px solid #007bff;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        ul#suggestions li:hover {
            background-color: #f0f8ff;
        }

        ul#suggestions li a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        ul#suggestions li a:hover {
            text-decoration: underline;
        }
    </style>

    <script>
    async function fetchSuggestions(query) {
        const res = await fetch('suggest.php?q=' + encodeURIComponent(query));
        const suggestions = await res.json();

        const list = document.getElementById("suggestions");
        list.innerHTML = '';
        suggestions.forEach(file => {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = "outputs/" + encodeURIComponent(file);
            link.innerText = file;
            link.download = file;
            li.appendChild(link);
            list.appendChild(li);
        });
    }

    function handleInput(event) {
        const query = event.target.value;
        if (query.length >= 1) {
            fetchSuggestions(query);
        } else {
            document.getElementById("suggestions").innerHTML = '';
        }
    }
    </script>
</head>
<body>
    <h1>🔍 ค้นหาใบประกาศ</h1>
    <div class="search-container">
        <input type="text" oninput="handleInput(event)" placeholder="พิมพ์ชื่อหรือนามสกุล" />
        <ul id="suggestions"></ul>
    </div>
</body>
</html>
