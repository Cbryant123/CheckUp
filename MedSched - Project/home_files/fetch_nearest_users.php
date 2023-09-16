<?php
$stmt = $conn->prepare("SELECT *, (x - ?) as dx, (y - ?) as dy, SQRT(POW(x - ?, 2) + POW(y - ?, 2)) as distance FROM users WHERE username <> ? ORDER BY distance LIMIT 5");
$stmt->bind_param("dddds", $_SESSION['x'], $_SESSION['y'], $_SESSION['x'], $_SESSION['y'], $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$search_results = array();
while ($row = $result->fetch_assoc()) {
    $search_results[] = $row;
}
$_SESSION['search_results'] = $search_results;
$stmt->close();
?>