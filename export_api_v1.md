# blaulichtSMS participant export API
## General

### Encoding
The encoding shall be UTF-8.

### Test base URL
https://api-staging.blaulichtsms.net/blaulicht

### Live base URL
https://api.blaulichtsms.net/blaulicht

### Export of recipients - JSON
_**/api/public/v1/recipient/{customerId}/export**_

To export recipients as a JSON for a customer ID, send a HTTP GET request with the following **headers** to the above URL:

- `Accept: application/json`
- `X-Username: {username}`
- `X-Password: {password}`
