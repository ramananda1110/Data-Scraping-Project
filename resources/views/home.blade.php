<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Scraping Project</title>
    
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            max-width: 550px;
        }
        .btn-custom {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
            display: block;
            margin-bottom: 10px;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #218838);
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #b52b38);
            border: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="mb-3">📊 Data Scraping Project</h1>
        <p class="text-muted">Efficiently scrape and manage data with ease.</p>

        <div class="mt-4">
            <!-- <a href="{{ route('scrape.data') }}" class="btn btn-success btn-custom">🔍 Scrape Data</a>
            <a href="{{ route('view.data') }}" class="btn btn-primary btn-custom">📄 View Data</a> -->
            <a href="{{ route('requisitions.bangla') }}" class="btn btn-success btn-custom">📌 ৭ম গণবিজ্ঞপ্তির বাংলা প্রভাষক পদের তালিকা</a>

            <a href="{{ route('requisitions.index_7th') }}" class="btn btn-success btn-custom">📌 ৭ম গণবিজ্ঞপ্তির শূন্যপদের তালিকা</a>
            <a href="{{ route('requisitions.index') }}" class="btn btn-success btn-custom">📌 ৬ষ্ঠ গণবিজ্ঞপ্তির শূন্যপদের তালিকা</a>
            <a href="{{ route('requisitions.district') }}" class="btn btn-primary btn-custom">📍 বাংলা প্রভাষক ৭ম গণবিজ্ঞপ্তি জেলাভিত্তিক পদের তালিকা: </a>
            <a href="{{ route('subjects.bangla') }}" class="btn btn-danger btn-custom">📘 Bangla Candidates</a>

            <a href="{{ route('recommended.index') }}" class="btn btn-success btn-custom">📘 Recommended Result  Bangla Lecturer</a>
            <a href="{{ route('district-wise.marks') }}" class="btn btn-danger btn-custom">📘 জেলা ভিত্তিক সুপারিশের  মার্ক তালিকা  </a>

        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
