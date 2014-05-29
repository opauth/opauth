Response
========

Opauth now returns a ``Response`` object, which stores the result of a successful authentication. 
A Response object must have five properties accesible publicly: `provider`, `raw`, `uid`, `name`, and `credentials`. 

Response properties
------

* `provider` - The provider with which the user authenticated (e.g. 'Twitter' or 'Facebook')
 
* `raw` - An array of all information gather about a user returned by the provider.

* `uid` - A user identifier unique to the given provider, such as a Twitter user ID.

* `name` - A user name unique to the given provider, such as a Facebook username.

* `credentials` - If the authenticating service provides some kind of access token or other credentials upon authentication, these are passed through here.

* `info` - An array containing information about the user, such as name, image, location, etc.
