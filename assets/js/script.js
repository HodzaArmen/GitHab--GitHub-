const BASE_URL = "http://localhost/githab/";
document.addEventListener('DOMContentLoaded', function () {
    // Confirm before deleting
    document.querySelectorAll('.delete-confirm').forEach(function (element) {
        element.addEventListener('click', function (event) {
            if (!confirm('Are you sure you want to delete this?')) {
                event.preventDefault();
            }
        });
    });

    // Markdown preview for issue/comment forms
    document.querySelectorAll('.markdown-preview').forEach(function (element) {
        element.addEventListener('click', function (event) {
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

    document.querySelectorAll('.star-form').forEach(form => {
        const button = form.querySelector('.star-button');

        button.addEventListener('click', async (event) => {
            event.preventDefault();

            const repoId = form.querySelector('input[name="repo_id"]').value;
            const action = form.querySelector('input[name="action"]').value;
            const starCountSpan = form.querySelector('.star-count');

            console.log('Repo ID:', repoId); 
            console.log('Action:', action); 

            try {
                const formData = new FormData(form);
                const response = await fetch(BASE_URL + 'repo/star', { 
                    method: 'POST',
                    body: formData
                });

                console.log('Response:', response); 

                if (response.ok) {
                    const data = await response.json();

                    console.log('Data:', data); 

                    if (data.success) {
                        const newAction = (action === 'star') ? 'unstar' : 'star';
                        const newText = (action === 'star') ? 'Starred' : 'Star';
                        const newClass = (action === 'star') ? 'btn-warning' : 'btn-outline-warning';

                        button.classList.remove('btn-warning', 'btn-outline-warning');
                        button.classList.add(newClass);
                        button.innerHTML = `<i class="bi bi-star-fill"></i> ${newText} (<span class="star-count">${data.starCount}</span>)`;

                        form.querySelector('input[name="action"]').value = newAction;
                    } else {
                        console.error('Star action failed:', data.message);
                        alert('Failed to update star. Please try again.');
                    }
                } else {
                    console.error('HTTP error:', response.status);
                    alert('Failed to update star. Please try again.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Failed to update star. Please try again.');
            }
        });
    });
});