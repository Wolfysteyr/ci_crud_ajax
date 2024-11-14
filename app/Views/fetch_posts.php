<?php if ($posts): ?>
    <?php foreach ($posts as $post): ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <a href="#" id="<?= $post['id']; ?>" data-bs-toggle="modal" data-bs-target="#detail_post_modal" class="post_detail_btn">
                    <img src="uploads/avatar/<?= $post['image']; ?>" class="img-fluid card-img-top">
                </a>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title fs-5 fw-bold"><?= $post['title']; ?></div>
                        <div class="badge bg-dark"><?= $post['category']; ?></div>
                    </div>
                    <p><?= substr($post['body'], 0, 80); ?>...</p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="fst-italic"><?= date('d F Y', strtotime($post['created_at'])); ?></div>
                    <div>
                        <a href="#" id="<?= $post['id']; ?>" data-bs-toggle="modal" data-bs-target="#edit_post_modal" class="btn btn-success btn-sm post_edit_btn">Edit</a>
                        <a href="#" id="<?= $post['id']; ?>" class="btn btn-danger btn-sm post_delete_btn">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-secondary text-center fw-bold my-5">No posts found in the database!</div>
<?php endif; ?>
