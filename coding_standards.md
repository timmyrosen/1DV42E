# Coding Standards

## Indents
One tab should be translated to four spaces. The number one reason to this is because lots of editors interprets a tab in their own style. Often when you change editor, view source code in a browser or publishing code to services like Github, the code structure breaks.

In the example code below each `-` represents a space.
```
if (...) {
    doSomething();
----doSomething();
}
```

One exception to the _four spaces_ rule may be when programming HTML. Using __two spaces__ gives a more readable view of the code and is very much recommended.
```
<html>
  <head>
    <title>My title</title>
  </head>
  <body>
    <div id="wrapper"></div>
  </body>
</html>
```

## Language
In a project, there should be only one spoken language. Everything should be written in the chosen language, including variables, functions, classes and comments.

## Naming convention
__variables:__  
`$variableName`

__functions:__  
`public function myFunction() {`

__classes:__  
`class MyClass {`

__file names:__  
`MyClassFile.php`

__folders:__  
`my_folder`

## Row length
Each row may be up to 150 characters long. This includes spaces.

This is a recommended way of structuring arrays which many values and keys.
```
$someArray = array(
                'one' => 1,
                'two' => 2,
                'three' => 3,
                'four' => 4,
                'five' => 5
);
```

Try to reduce nesting as much as possible. Nesting builds lengthy rows. Instead of nesting, the following is a better approach.
```
private function someFunction($variable=false) {
    if (!$variable) {
        return false;
    }

    for (...)
        some more code...
}
```

## Curly brackets
Always on the same row as the statement.

`if (...) {`

In some short statements it's possible to exclude the curly brackets, but __don't__! To increase the readability it's recommended to always include them.

## if, else if, else
```
if (...) {
  
}
else if (...) {
  
}
else {
  
}
```

## Returns
Use lowercase letters when returning a boolean.

```
return true;
return false;
return $variable;
```

## Statements
```
if (...) {
for (...) {
while (...) {
```

## Comments

```
/**
* Some description of the function.
* @param   string  $var1
* @param   bool    $var2  some description
* @param   int     $var3
* @param   array   $var4
* @throws  Exception      some description
* @return  void
* @todo    do some more stuff
*/
public function someFunction($var1, $var2, $var3, $var4) {
```

```
// some description of the
// code below.
if (something) {
    dowhatever();
    ...
}
```

## Classes
There should only be one class per file.

__public__:  
Visible and editable for all outside classes.  
`public $myVar = 'My value';`

__private__:  
Invisible and uneditable for all outside classes.  
`private $myVar = 'My value';`

__protected__:  
Visible and uneditable for all outside classes.  
`protected $myVar = 'My value';`

__const__:  
Visible and uneditable for all outside classes.  
`const MYVAR = 'My value';`

__static__:  
Statics visibility can be controlled by public and private.  
`public static $myVar = 'My value';`

The variable can be accessed like this:  
```
// inside its own class
self::$myVar

// outside its own class
SomeClass::$myVar
```

## Constructors and desctructors
Constructors should be named as `__construct` and not the class name.

```
public function __construct() {
    
public function __destruct() {
```

## Functions/Methods
It's highly recommended to use strong typing in functions. This reduces the risk of someone using the function incorrectly.
```
private function Signup(Member $member) {
```

A function or method should always have only one return type. If a function expects an array, then return an empty array if there was no result.
```
return array();
return 0;
return "";
```

__Do not__ return a value (_like false_) if something went wrong. It's better to throw an exception in that scenario.
```
throw new Exception('Error message');
```

## Assert
Assert is a great way to reduce the risk of someone using a function incorrectly. Asserts can later be inactivated to give better performance.

```
assert(is_array($someArray), 'An array is excepted.');
assert(is_int($someVar) && $someVar > 0, 'Positive int is expected.');
```

Some example configs showing how to call your own callback. Maybe in the purpose of logging the error.
```
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, 'Debug::AssertCallback');
```

## @todo
namespaces
globals, post, get, session
"rules" about string dependings? How does it work between files?
use of ===?
