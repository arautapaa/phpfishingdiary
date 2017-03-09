
<!DOCTYPE html>
<html>
	<head>
		<title>Kirjaudu</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		<a href="/register/">Rekisteröidy</a>
		<form action="/performlogin/" method="POST">
			<input name="username" value="" placeholder="Käyttäjätunnus"/><br/>
			<input name="password" type="password" value="" placeholder="Salasana"/>
			<input type="submit" value="Kirjaudu sisään" /> 
		</form>
	</body>
</html>