<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD App Using CI 4 and Ajax</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


  <script src="/jsonform-2.2.5/deps/jquery.min.js"></script>
  <script src="/jsonform-2.2.5/deps/underscore.js"></script>
  <script src="/jsonform-2.2.5/deps/opt/jsv.js"></script>
  <script src="/jsonform-2.2.5/lib/jsonform.js"></script>
  

</head>

<body>
  <!-- add new post modal start -->
  <div class="modal fade" id="add_post_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Add New Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="add_post_form"></div> <!-- Placeholder for JSONForm -->
      </div>
    </div>
  </div>
  <!-- add new post modal end -->

  <!-- edit post modal start -->
  <div class="modal fade" id="edit_post_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Edit Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" method="POST" enctype="multipart/form-data" id="edit_post_form" novalidate>
          <input type="hidden" name="id" id="pid">
          <input type="hidden" name="old_image" id="old_image">
          <div class="modal-body p-5">
            <div class="mb-3">
              <label>Post Title</label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
              <div class="invalid-feedback">Post title is required!</div>
            </div>

            <div class="mb-3">
              <label>Post Category</label>
              <input type="text" name="category" id="category" class="form-control" placeholder="Category" required>
              <div class="invalid-feedback">Post category is required!</div>
            </div>

            <div class="mb-3">
              <label>Post Body</label>
              <textarea name="body" class="form-control" rows="4" id="body" placeholder="Body" required></textarea>
              <div class="invalid-feedback">Post body is required!</div>
            </div>

            <div class="mb-3">
              <label>Post Image</label>
              <input type="file" name="file" class="form-control">
              <div class="invalid-feedback">Post image is required!</div>
              <div id="post_image"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="edit_post_btn">Update Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- edit post modal end -->

  <!-- detail post modal start -->
  <div class="modal fade" id="detail_post_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Details of Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img src="" id="detail_post_image" class="img-fluid">
          <h3 id="detail_post_title" class="mt-3"></h3>
          <h5 id="detail_post_category"></h5>
          <p id="detail_post_body"></p>
          <p id="detail_post_created" class="fst-italic"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- detail post modal end -->

  <div class="container">
    <div class="row my-4">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div class="text-secondary fw-bold fs-3">All Posts</div>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#add_post_modal">Add New Post</button>
          </div>
          <div class="card-body">
            <div class="row" id="show_posts">
              <h1 class="text-center text-secondary my-5">Posts Loading..</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    $(function() {
      // Initialize JSONForm
      $('#add_post_form').jsonForm({
        schema: {
          title: {
            type: 'string',
            title: 'Post Title',
            required: true
          },
          category: {
            type: 'string',
            title: 'Post Category',
            required: true
          },
          body: {
            type: 'string',
            title: 'Post Body',
            required: true
          },
          image: {
            type: 'string',
            title: 'Post Image',
            required: true
          }
        },
        form: [
          "title",
          {
            "key": "category",
            "type": "text"
          },
          {
            "key": "body",
            "type": "textarea",
            "placeholder": "Enter the body of the post here..."
          },
          {
            "key": "image",
            "type": "file",
            "placeholder": "Choose an image file"
          }
        ],
        onSubmit: function (errors, values) {
          if (errors) {
            alert('Validation errors: ' + errors.join(', '));
          } else {
            submitForm(values);
          }
        }
      });

      // Bind submit button to form submission
      $('#add_post_btn').on('click', function() {
        $('#add_post_form').submit();
      });

      // AJAX form submission
      function submitForm(values) {
        $("#add_post_btn").text("Adding...");
        const formData = new FormData();
        for (const key in values) {
          if (values.hasOwnProperty(key)) {
            formData.append(key, values[key]);
          }
        }

        $.ajax({
          url: '<?= base_url('post/add') ?>',
          method: 'post',
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.error) {
              $("#image").addClass('is-invalid');
              $("#image").next().text(response.message.image);
            } else {
              $("#add_post_modal").modal('hide');
              $("#add_post_form")[0].reset();
              $("#image").removeClass('is-invalid');
              $("#image").next().text('');
              Swal.fire(
                'Added',
                response.message,
                'success'
              );
              fetchAllPosts();
            }
            $("#add_post_btn").text("Add Post");
          }
        });
      }
    });

      // edit post ajax request
      $(document).delegate('.post_edit_btn', 'click', function(e) {
        e.preventDefault();
        const id = $(this).attr('id');
        $.ajax({
          url: '<?= base_url('post/edit/') ?>/' + id,
          method: 'get',
          success: function(response) {
            $("#pid").val(response.message.id);
            $("#old_image").val(response.message.image);
            $("#title").val(response.message.title);
            $("#category").val(response.message.category);
            $("#body").val(response.message.body);
            $("#post_image").html('<img src="<?= base_url('uploads/avatar/') ?>/' + response.message.image + '" class="img-fluid mt-2 img-thumbnail" width="150">');
          }
        });
      });

      // update post ajax request
      $("#edit_post_form").submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        if (!this.checkValidity()) {
          e.preventDefault();
          $(this).addClass('was-validated');
        } else {
          $("#edit_post_btn").text("Updating...");
          $.ajax({
            url: '<?= base_url('post/update') ?>',
            method: 'post',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
              $("#edit_post_modal").modal('hide');
              Swal.fire(
                'Updated',
                response.message,
                'success'
              );
              fetchAllPosts();
              $("#edit_post_btn").text("Update Post");
            }
          });
        }
      });

      // delete post ajax request
      $(document).delegate('.post_delete_btn', 'click', function(e) {
        e.preventDefault();
        const id = $(this).attr('id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '<?= base_url('post/delete/') ?>/' + id,
              method: 'get',
              success: function(response) {
                Swal.fire(
                  'Deleted!',
                  response.message,
                  'success'
                )
                fetchAllPosts();
              }
            });
          }
        })
      });
      // post detail ajax request
      $(document).delegate('.post_detail_btn', 'click', function(e) {
        e.preventDefault();
        const id = $(this).attr('id');
        $.ajax({
          url: '<?= base_url('post/detail/') ?>/' + id,
          method: 'get',
          dataType: 'json',
          success: function(response) {
            $("#detail_post_image").attr('src', '<?= base_url('uploads/avatar/') ?>/' + response.message.image);
            $("#detail_post_title").text(response.message.title);
            $("#detail_post_category").text(response.message.category);
            $("#detail_post_body").text(response.message.body);
            $("#detail_post_created").text(response.message.created_at);
          }
        });
      });

      // fetch all posts ajax request
      fetchAllPosts();

      function fetchAllPosts() {
        $.ajax({
          url: '<?= base_url('post/fetch') ?>',
          method: 'get',
          success: function(response) {
            $("#show_posts").html(response.message);
          }
        });
      };
  </script>

</body>

</html>