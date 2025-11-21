<?php
$url = $_SERVER['REQUEST_URI'];

if (strpos($url, '/ar/list') !== false)
	$flag = 'ar';
if (strpos($url, '/Target/') !== false || strpos($url, '/points_full/') !== false || strpos($url, '/targetBags/') !== false || strpos($url, '/custom_pp/') !== false)
	$flag = 'target';
?>
<aside class="sidebar">
	<nav class="nav">
		<a href="../ar/list.php?brand=acc"><img style="margin-left:40px;" src="../images/ultra_logo.png"></a>
		<ul>
			<li <?php if ($flag == 'ar') echo 'class="active"';?>><a href="../ar/list.php?brand=ut">AR List</a></li>
			<li <?php if ($flag == 'target') echo 'class="active"';?>><a <?php if ($flag == 'target') {echo 'href="#"';} else{echo 'href="../Target/ut_list.php?"';}?>>Target</a></li>
			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
			<!--li><a href="../SpecialTarget/ultra_list.php?">Special Target</a></li>
			<li><a href="../gold_points/ultra_list.php?">Gold Points</a></li>
			<li><a href="../redemption/ultra_list.php?">Redemption</a></li-->
		</ul>
	</nav>
</aside>