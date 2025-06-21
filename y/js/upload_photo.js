$(document).ready(function () {
  $('#edit_photo').click(function () {
      const uploadDiv = `
                    <div id="overlay">
                      <div id="upload_modal">
                        <div class="flex-column">
                          <div class="group">
                            <h5>Upload a Profile Picture</h5>
                          </div>
                          <img id="preview_img" src="" style="max-width: 150px; display: none; margin-top: 10px;" alt="Image preview">
                          <form id="upload_pic" name="upload_pic" method="post" enctype="multipart/form-data" action="process/upload_proc.php">
                            <div class="group">
                              <label for="profile_pic" class="btn-main">Choose File</label>
                              <input type="file" id="profile_pic" name="profile_pic" class="invisible" required>
                            </div>
                            <div class="group">
                              <input type="submit" id="upload" value="Upload" class="btn-main">
                            </div>
                          </form>
                          <button id="cancel_pic" class="btn-main">Cancel</button>
                        </div>
                      </div>
                    </div>`;

      $('body').append(uploadDiv);
  });

  $('body').on('change', '#profile_pic', function () {
    const file = this.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#preview_img').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    } else {
        $('#preview_img').hide();
    }
  });

  $('body').on('click', '#cancel_pic', function() {
    $('#overlay').remove();
  });

});