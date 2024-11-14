//need to continue to update cart option in free time, its out of tasks adn assignemnts
<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Your cart</span>
                <span class="badge bg-primary rounded-pill"><?php echo count($basketItems); ?></span>
            </h4>
            <ul class="list-group mb-3">
                <?php if (!empty($basketItems)): ?>
                    <?php foreach ($basketItems as $item): ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-body-secondary">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="text-body-secondary">
                                    <?php echo number_format($item['price'], 2); ?> HUF
                                </span>
                                <br>
                                <small class="text-muted">
                                    Qty: <?php echo $item['quantity']; ?>
                                </small>
                            </div>
                        </li>
                    <?php endforeach; ?>

                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (HUF)</span>
                        <strong><?php echo number_format($totalPrice, 2); ?> HUF</strong>
                    </li>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted">
                        Your cart is empty
                    </li>
                <?php endif; ?>
            </ul>

            <button 
                class="w-100 btn btn-primary btn-lg" 
                type="submit"
                <?php echo empty($basketItems) ? 'disabled' : ''; ?>
            >
                Continue to Checkout
            </button>
        </div>
    </div>
</div>