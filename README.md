# PostgreSQL-easy-class

PostgreSQ Easy to use PHP Class, from PHPSnipe framework. Compatible with the Mysqli Easy Class from the same author.

Include it and use it like this

```
$db = new DatabasePG();
$sql = "select * from settings";
$db->query($sql);
while ($db->next()) {
	$field = $login->rs['name'];
	$settings->$field = $login->rs['value'];
	$settings->description[$field] = $login->rs['description'];
}
```
