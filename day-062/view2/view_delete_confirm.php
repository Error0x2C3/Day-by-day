<div class="modal-content bg-dark text-light p-4 rounded">

    <div class="text-center mb-3">
        <i class="bi bi-trash text-danger fs-1"></i>
    </div>

    <h4 class="text-danger text-center fw-bold">
        Are you sure?
    </h4>

    <hr>

    <p class="text-center">
        Do you really want to delete item
        <strong>"<?= htmlspecialchars($name) ?>"</strong>
        by <strong><?= htmlspecialchars($owner) ?></strong>
        and all of its dependencies?
    </p>

    <p class="text-center text-muted">
        This process cannot be undone.
    </p>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="items/cancel" class="btn btn-secondary">
            Cancel
        </a>

        <form method="post" action="items/delete">
            <input type="hidden" name="item_id" value="<?= $item_id ?>">
            <button type="submit" class="btn btn-danger">
                Delete
            </button>
        </form>
    </div>

</div>
