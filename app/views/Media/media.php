<div class="media">
	<div class="menu">
		<div class="types">
			<!-- Show current based on the type of media selected -->
			<a <?php echo $type == 'image' ? 'class="current"' : ''; ?> href="/media?type=image">Photos</a>
			<a <?php echo $type == 'video' ? 'class="current"' : ''; ?> href="/media?type=video">Video</a>
			<a <?php echo $type == 'audio' ? 'class="current"' : ''; ?> href="/media?type=audio">Audio</a>
			<a <?php echo $type == 'document' ? 'class="current"' : ''; ?> href="/media?type=document">Documents</a>
		</div>
		<div class="upload">
			<label class="upload-btn" for="file-input">Upload</label>
			<input type="file" id="file-input" class="file-input" accept=".jpeg,.jpg,.png,.gif,.mpeg,.wav,.mp3,.mp4,.pdf,.doc,.docx">
			<label class="upload-btn" id="delete-button">Delete</button>

		</div>
			<div id="delete-message" class="hidden">Select file to delete</div>
	</div>
	<div class="content"></div>
</div>