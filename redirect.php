<?php
$totalAmount = isset($_GET['total']) ? $_GET['total'] : 0;
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$client_ip = isset($_GET['client_ip']) ? $_GET['client_ip'] : '';
?>

<html>
<body>
<form id="redirectForm" action="process_payment.php" method="post">
    <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
    <input type="hidden" name="orderId" value="<?php echo $order_id; ?>">
    <input type="hidden" name="clientIp" value="<?php echo $client_ip; ?>">
</form>

<script>
    document.getElementById('redirectForm').submit();
</script>
</body>
</html>