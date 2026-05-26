function fetchOrderItems(orderID) {
    window.location.href = "drug-order.php?orderID=" + orderID;
}

function confirmOrder() {
    const totalCost = document.getElementById("totalCost").value;
    const orderID = document.getElementById("confirmOrderID").value;

    if (!totalCost || !orderID) {
        alert("Please select an order and enter total cost.");
        return false;
    }

    return confirm("Confirm this order?");
}

function orderShipped() {
    const orderID = document.getElementById("shipOrderID").value;

    if (!orderID) {
        alert("Please select an order.");
        return false;
    }

    return confirm("Mark this order as shipped?");
}