Opauth
=======
__This project is still under heavy development. DO NOT USE.__  
Initial stable release is scheduled for late May 2012.

Opauth is a multi-provider authentication framework for PHP, inspired by [OmniAuth for Ruby](https://github.com/intridea/omniauth).

What is Opauth?
---------------
Opauth provides a standardized way to interface with 3rd-party authentication providers. Unlike many current authentication frameworks, Opauth does not deal with database, so developers are not forced to adhere to predetermined database schema.

Opauth is a PHP library. It can be used as a standalone authentication interface (sending HTTP traffic to it), or as a plugin (instantiation of Opauth). 
Opauth works well with other PHP frameworks, such as [CakePHP](https://github.com/uzyn/cakephp-opauth), [Yii](https://github.com/kahwee/yii-opauth), etc.

Opauth as a framework provides API that allows authentication providers to develop strategies that work in a predictable manner.


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