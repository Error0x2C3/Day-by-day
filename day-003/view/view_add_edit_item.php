<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> 
</head>

<body class="p-3">

    <form action="AddEditItem/save" method="POST">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="#" class="btn btn-secondary">&#8592; Retour</a>
            <h2 class="m-0">Add Item</h2>

            <button type="submit" class="btn btn-success">Save</button>
        </div>

        <div class="mb-4 border p-3">
            <h4>Basic Information</h4>

            <div class="mb-3">
                <label class="form-label">Item Title *</label>
                <input type="text" name="title" class="form-control" placeholder="Enter item title" required>
                <div class="text-danger small">Title length must be between 3 and 255 characters.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Enter description"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Sale Duration (days) *</label>
                <input type="number" name="duration" class="form-control" value="7" required>
            </div>
        </div>

        <div class="mb-4 border p-3">
            <h4>Sale Type</h4>

            <div class="border p-3 mb-3">
                <h5>Option 1: Auction</h5>

                <div class="mb-3">
                    <label class="form-label">Starting Bid</label>
                    <div class="input-group">
                        <input type="number" name="starting_bid" class="form-control" value="100">
                        <span class="input-group-text">€</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Instant Purchase Price (optional)</label>
                    <div class="input-group">
                        <input type="number" name="buy_now_price" class="form-control" value="10">
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="text-danger small">Buy now price must be greater than starting bid.</div>
                </div>
            </div>

            <div class="border p-3">
                <h5>Option 2: Direct Sale</h5>

                <div class="mb-3">
                    <label class="form-label">Sale Price</label>
                    <div class="input-group">
                        <input type="number" name="direct_price" class="form-control" placeholder="e.g., 150.00">
                        <span class="input-group-text">€</span>
                    </div>
                </div>
            </div>
        </div>

         <nav class="border-top pt-3 d-flex justify-content-around">
            <a href="#" class="btn btn-outline-secondary">Browse</a>
            <a href="#" class="btn btn-outline-secondary">My Items</a>
            <a href="#" class="btn btn-primary">Add Item</a>
            <a href="#" class="btn btn-outline-secondary">Profile</a>
        </nav>


        <div class="d-flex gap-2 mb-5">
            <button type="button" class="btn btn-primary">+1h</button>
            <button type="button" class="btn btn-primary">+1d</button>
            <button type="button" class="btn btn-primary">+1w</button>
            <button type="button" class="btn btn-danger">Reset</button>
        </div>

    </form>

   
</body>
</html>
