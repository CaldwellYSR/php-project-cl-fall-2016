<?php 
	// Setup database and initialize tank variable
	require_once(__DIR__."/includes/functions.php"); 
	$db = get_db();
	$tanks = get_tanks($db);
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
				<th>&nbsp;</th>
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
	<form action="new_tank.php" method="POST">
		<label for="name">Name: 
			<input type="text" id="name" name="name" />
		</label>
		<label for="nation">Nation:
			<select name="nation" id="nation">
				<option value="USSR">USSR</option>
				<option value="GERMANY">Germany</option>
				<option value="USA">U.S.A.</option>
				<option value="CHINA">China</option>
				<option value="FRANCE">France</option>
				<option value="UK">U.K.</option>
				<option value="JAPAN">Japan</option>
				<option value="CZECHOSLOVAKIA">Czechoslovakia</option>
				<option value="SWEDEN">Sweden</option>
			</select>
		</label>
		<label for="type">Type:
			<select name="type" id="type">
				<option value="Light Tank">Light Tank</option>
				<option value="Medium Tank">Medium Tank</option>
				<option value="Heavy Tank">Heavy Tank</option>
				<option value="Tank Destroyer">Tank Destroyer</option>
				<option value="SPG">Artillery Tank</option>
			</select>
		</label>
		<label for="tier">Tier:
			<select name="tier" id="tier">
				<option value="1">I</option>
				<option value="2">II</option>
				<option value="3">III</option>
				<option value="4">IV</option>
				<option value="5">V</option>
				<option value="6">VI</option>
				<option value="7">VII</option>
				<option value="8">VII</option>
				<option value="9">IX</option>
				<option value="10">X</option>
			</select>
		</label>
		<label for="hit_points">HP: 
			<input type="number" min="1" step="1" id="hit_points" name="hit_points" />
		</label>
		<label for="damage">Standard Shell Damage: 
			<input type="number" min="1" step="1" id="damage" name="damage" />
		</label>
		<label for="penetration">Standard Shell Penetration: 
			<input type="number" min="1" step="1" id="penetration" name="penetration" />
		</label>
		<label for="damage_per_minute">DPM: 
			<input type="number" min="1" step="1" id="damage_per_minute" name="damage_per_minute" />
		</label>
		<input class="button" type="submit" name="submit" id="submit" value="Submit" />
	</form>
</body>
</html>