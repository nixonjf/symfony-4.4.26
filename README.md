# symfony-4.4.26

Login boilerplate using Json Web Token


Register user<br />
curl -X POST -H "Content-Type: application/json" http://localhost:82/api/register -d '{"username":"admin@admin.com","password":"1"}'


Login first time<br />
curl -X POST -H "Content-Type: application/json" http://localhost:82/api/login_check -d '{"username":"admin@admin.com","password":"1"}'

Subsequent time<br />
curl -X  GET  http://localhost:82/api/sample -H "Authorization: Bearer  xxxx”
