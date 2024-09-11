<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles | Visa Social Media Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/articles-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
</head>

<body>

    <div class="container">
        <h1>Articles</h1>

        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search articles...">
            <button onclick="searchArticles()">Search</button>
        </div>

        <div class="article-list" id="article-list">
            <!-- Article cards will be dynamically inserted here -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        // Sample article data
        const articles = [{
                id: 1,
                title: "Top 10 Visa Application Tips",
                date: "2023-05-15",
                summary: "Essential tips to ensure a smooth visa application process for international travelers.",
            },
            {
                id: 2,
                title: "Understanding Student Visa Requirements",
                date: "2023-05-10",
                summary: "A comprehensive guide to student visa requirements for various popular study abroad destinations.",
            },
            {
                id: 3,
                title: "Business Visa Etiquette: Do's and Don'ts",
                date: "2023-05-05",
                summary: "Learn about the proper etiquette and common pitfalls when applying for and using a business visa.",
            },
            {
                id: 4,
                title: "Digital Nomad Visas: A New Trend",
                date: "2023-05-01",
                summary: "Exploring the rise of digital nomad visas and countries offering them to remote workers.",
            },
            {
                id: 5,
                title: "Visa-Free Travel: Top Destinations",
                date: "2023-04-25",
                summary: "Discover the best destinations that offer visa-free or visa-on-arrival access for many nationalities.",
            },
        ];

        function createArticleCard(article) {
            const card = document.createElement('div');
            card.className = 'article-card';
            card.innerHTML = `
                <h2 class="article-title">${article.title}</h2>
                <p class="article-date">${moment(article.date).format('MMMM D, YYYY')}</p>
                <p class="article-summary">${article.summary}</p>
                <div class="article-actions">
                    <button class="view-btn" onclick="viewArticle(${article.id})">View</button>
                    <button class="edit-btn" onclick="editArticle(${article.id})">Edit</button>
                    <button class="delete-btn" onclick="deleteArticle(${article.id})">Delete</button>
                </div>
            `;
            return card;
        }

        function displayArticles(articlesToDisplay) {
            const articleList = document.getElementById('article-list');
            articleList.innerHTML = '';
            articlesToDisplay.forEach(article => {
                articleList.appendChild(createArticleCard(article));
            });
        }

        function searchArticles() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const filteredArticles = articles.filter(article =>
                article.title.toLowerCase().includes(searchTerm) ||
                article.summary.toLowerCase().includes(searchTerm)
            );
            displayArticles(filteredArticles);
        }

        function viewArticle(id) {
            alert(`Viewing article ${id}`);
            // Implement view functionality
        }

        function editArticle(id) {
            alert(`Editing article ${id}`);
            // Implement edit functionality
        }

        function deleteArticle(id) {
            if (confirm(`Are you sure you want to delete article ${id}?`)) {
                alert(`Article ${id} deleted`);
                // Implement delete functionality
            }
        }

        // Initial display of articles
        displayArticles(articles);
    </script>
</body>

</html>