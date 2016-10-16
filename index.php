<?php 
	// Setup database and initialize tank variable
	require_once(__DIR__."/includes/functions.php"); 
	$tanks = get_tanks();
?>
<!Doctype html />
<html>
<head>
	<title>World of Tanks -- PHP Project</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<h1>World of Tanks</h1>
	<h3>Tank Destroyers</h3>
	<!-- Render table for currently pulled tanks. Can be pulled into a function later if need be -->
	<table>
		<thead>
			<tr>
				<th>Nation</th>
				<th>Tier</th>
				<th>Type</th>
				<th>Name</th>
				<th>HP</th>
				<th>DMG</th>
				<th>PEN</th>
				<th>DPM</th>
				<th colspan="2">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			// Loop through each tank and print a table row with it's details 
			if ($tanks && count($tanks) > 0) : 
				foreach ($tanks as $tank) : ?>
				<tr>
					<td><?php echo $tank->nation; ?></td>
					<td><?php echo roman_numeral($tank->tier); ?></td>
					<td><?php echo $tank->type; ?></td>
					<td><?php echo $tank->name; ?></td>
					<td><?php echo $tank->hit_points; ?></td>
					<td><?php echo $tank->damage; ?></td>
					<td><?php echo $tank->penetration; ?></td>
					<td><?php echo $tank->damage_per_minute; ?></td>
					<td class="update"><a href="update_tank.php?id=<?php echo $tank->id; ?>">&#x270E;</a></td>
					<td class="delete"><a href="delete_tank.php?id=<?php echo $tank->id; ?>">X</a></td>
				</tr> <?php
				endforeach;
			else : ?>
			<tr>
				<td colspan="8">There were no tanks found with those specs, please search again</td>
			</tr> <?php
		endif; ?>
		</tbody>
	</table>
	<hr />
	<!-- Render new tank form -->
	<h3>Create a new tank</h3>
    <?php render_form(); ?>
</body>
</html>
