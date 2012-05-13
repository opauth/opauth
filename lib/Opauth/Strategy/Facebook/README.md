Opauth Facebook
===============
[Opauth][1] strategy for Facebook authentication.

Implemented based on https://developers.facebook.com/docs/authentication/


Getting Started
---------------
1. Set up [Opauth][1]
2. Place this at `path_to_opauth/lib/Opauth/Strategy/Facebook/`
3. Add the following to Opauth config's `Strategy` array:

	```php
	<?php
		'Facebook' => array(
			'app_id' => 'YOUR OWN FACEBOOK APP ID',
			'app_secret' => 'YOUR OWN FACEBOOK APP SECRET KEY'
		)
	```

4. Send users to `://path_to_opauth/facebook` for authentication.

Strategy parameters
-----------------------

### Required
`app_id`, `app_secret`

### Optional
`scope`, `state`, `response_type`, `display`

Refer to Facebook's [OAuth Dialog documentation](https://developers.facebook.com/docs/reference/dialogs/oauth/) for details on these parameters

License
---------
The MIT License  
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.

[1]: https://github.com/uzyn/opauth	"Opauth"