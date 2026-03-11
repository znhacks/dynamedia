document.addEventListener('DOMContentLoaded', function () {
	// Avatar preview for profile edit page
	var avatarInput = document.getElementById('avatar');
	var previewContainer = document.getElementById('previewContainer');

	if (avatarInput && previewContainer) {
		avatarInput.addEventListener('change', function (e) {
			previewContainer.innerHTML = '';
			var file = avatarInput.files && avatarInput.files[0];
			if (!file) return;
			if (!file.type.startsWith('image/')) return;

			var reader = new FileReader();
			reader.onload = function (ev) {
				var wrapper = document.createElement('div');
				wrapper.className = 'preview-new';
				var img = document.createElement('img');
				img.src = ev.target.result;
				img.alt = 'Preview';
				wrapper.appendChild(img);
				previewContainer.appendChild(wrapper);
			};
			reader.readAsDataURL(file);
		});
	}

	// Confirm delete buttons (if present in other views)
	document.querySelectorAll('.delete-post-btn, .delete-comment-btn').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			var msg = btn.classList.contains('delete-post-btn') ? 'Yakin ingin menghapus post ini?' : 'Yakin ingin menghapus komentar?';
			if (!confirm(msg)) e.preventDefault();
		});
	});
});
