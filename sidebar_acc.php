<?php
$url = $_SERVER['REQUEST_URI'];

if (strpos($url, '/ar/list') !== false)
	$flag = 'ar';
if (strpos($url, '/Target/') !== false || strpos($url, '/points_full/') !== false || strpos($url, '/targetBags/') !== false || strpos($url, '/custom_pp/') !== false)
	$flag = 'target';
if (strpos($url, '/SpecialTarget') !== false)
	$flag = 'SpecialTarget';
if (strpos($url, '/gold_points') !== false)
	$flag = 'gold_points';
if (strpos($url, '/redemption') !== false)
	$flag = 'redemption';
?>
<aside class="sidebar">
	<nav class="nav">
		<a href="../ar/list.php?brand=ut"><img style="margin-left:40px;" src="../images/acc_logo.png"></a>
		<ul>
			<li <?php if ($flag == 'ar') echo 'class="active"';?>><a href="../ar/list.php?brand=acc">AR List</a></li>
			<li <?php if ($flag == 'target') echo 'class="active"';?>><a <?php if ($flag == 'target') {echo 'href="#"';} else{echo 'href="../Target/acc_list.php"';}?>>Target</a></li>
			<li <?php if ($flag == 'SpecialTarget') echo 'class="active"';?>><a <?php if ($flag == 'SpecialTarget') {echo 'href="#"';} else{echo 'href="../SpecialTarget/acc_list.php?"';}?>>Special Target</a></li>
			<li <?php if ($flag == 'gold_points') echo 'class="active"';?>><a <?php if ($flag == 'gold_points') {echo 'href="#"';} else{echo 'href="../gold_points/acc_list.php?"';}?>>Gold Points</a></li>
			<li <?php if ($flag == 'redemption') echo 'class="active"';?>><a <?php if ($flag == 'redemption') {echo 'href="#"';} else{echo 'href="../redemption/acc_list.php?"';}?>>Redemption</a></li>
		</ul>
	</nav>
</aside>