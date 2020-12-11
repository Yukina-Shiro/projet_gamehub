# Rest API for MVC-Core

In order to use this basic Rest API you need to :

1. Enable Apache 2.x mod_rewrite :

```
a2enmode rewrite; systemctl restart apache2.service
```
2. Check that the **.htaccess** file is present at the root of the mvc-core.

3. Have at least * AllowOverride FileInfo * or even * AllowOverride All * in the root folder of ** mvc-core ** for .htaccess files to be taken into account
(see example below).

```
<Directory /absolute_path_to_your_mvc-core/>
		Options -Indexes +FollowSymLinks +MultiViews
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
```

All the responses have got the following JSON format :

```
{
	"code": http response integer code,
	"info": "information message",
	"id": "null or object id",
	"data":{"object": "data", "in": "JSON", "array": "format"}
}
```

All the data resquests must be in JSON format (e.g. order object Create - POST) :

```
{
	"lastname":"BRUNEAU", "firstname":"Jean-Michel", "email":"jean-michel.bruneau@netspace.fr",
	"brand":"Renault","model":"Twingo",
	"gearbox":"robotic","color":"metallic",
	"options":"{\"reversing_radar\":\"reversing_radar\",\"air_conditioner\":\"air_conditioner\"}",
	"return_price":"799",
	"total_price":"11991"
}
```

To manually test this API see [README.md](test/README.md).
