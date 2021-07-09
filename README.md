# symfony-4.4.26

Auth boilerplate using Json Web Token
 
  
  Things done
  1. API to check login user (JWT)
  2. API to register user
  3. API test cases
  4. Admin login, forgot password and change password
  5. Integrated core UI
 
  ToDo:
  1.Tests for admin login, reset password and change password
  2.Translations
  3.UI optimisations
 
  
  
 




Register user<br />
curl -X POST -H "Content-Type: application/json" http://localhost:82/api/register -d '{"username":"admin@admin.com","password":"1"}'


Login first time<br />
curl -X POST -H "Content-Type: application/json" http://localhost:82/api/login_check -d '{"username":"admin@admin.com","password":"1"}'

Subsequent time<br />
curl -X  GET  http://localhost:82/api/sample -H "Authorization: Bearer  xxxx”


Test 

php ./vendor/bin/phpunit


JWT Reference
https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.md#installation
