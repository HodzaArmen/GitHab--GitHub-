document.addEventListener('DOMContentLoaded', function() {
    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm before deleting
    document.querySelectorAll('.delete-confirm').forEach(function(element) {
        element.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete this?')) {
                event.preventDefault();
            }
        });
    });

    // Markdown preview for issue/comment forms
    document.querySelectorAll('.markdown-preview').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            var textarea = document.querySelector(this.dataset.target);
            var preview = document.querySelector(this.dataset.preview);
            
            if (textarea && preview) {
                if (this.classList.contains('active')) {
                    // Show editor
                    preview.style.display = 'none';
                    textarea.style.display = 'block';
                    this.textContent = 'Preview';
                    this.classList.remove('active');
                } else {
                    // Show preview (would need a markdown library in real implementation)
                    preview.innerHTML = '<em>Markdown preview would appear here</em>';
                    preview.style.display = 'block';
                    textarea.style.display = 'none';
                    this.textContent = 'Edit';
                    this.classList.add('active');
                }
            }
        });
    });
});

// AJAX functions
function starRepo(repoId) {
    fetch(BASE_URL + 'repo/star', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({repo_id: repoId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update star count and button
            document.querySelector('.star-count').textContent = data.starCount;
            document.querySelector('.star-button').className = data.isStarred ? 
                'btn btn-sm btn-warning star-button' : 'btn btn-sm btn-outline-warning star-button';
            document.querySelector('.star-button').innerHTML = 
                `<i class="bi bi-star-fill"></i> ${data.isStarred ? 'Starred' : 'Star'} (${data.starCount})`;
        }
    });
}

// Define BASE_URL if not defined
if (typeof BASE_URL === 'undefined') {
    var BASE_URL = '/githab/';
}