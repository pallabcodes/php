<?php
// -------- Helper Function --------
function br($text = "") {
  echo $text . (php_sapi_name() === 'cli' ? "\n" : "<br>");
}



//////////////////// 1. Variables ////////////////////
$name = "John";             // string
$age = 30;                  // integer
$price = 99.99;             // float
$isActive = true;           // boolean
$colors = ["red", "blue"];  // array

br("---- 1. Variables ----");
br("Name: $name");
br("Age: $age");
br("Price: $price");
br("Is Active: " . ($isActive ? "true" : "false"));
br("Colors: " . implode(", ", $colors));
br();



//////////////////// 2. Constants ////////////////////
define("PI", 3.14);         
const GREETING = "Hello";   

class MathConstants {
  const PI = 3.14159;
}

echo MathConstants::PI; // Output: 3.14159

/*
In PHP:

const:
- Immutable constant (cannot be changed after definition)
- Compile-time only
- Allowed in global scope, classes, and interfaces
- Only scalar values (int, float, string, bool, array in PHP 5.6+)
- Cannot use runtime expressions

Difference: define() vs const
| Feature            | define()                      | const                      |
|-------------------|-------------------------------|----------------------------|
| Scope             | Global only                   | Global, class, interface   |
| Runtime/Compile   | Runtime                       | Compile-time               |
| Usage in classes  | ❌ Not allowed                | ✅ Allowed                 |
| Expressions       | Some runtime values allowed   | ❌ Only literals allowed   |

Example:
const GREETING = "Hello";          // global
class Demo {
  const VERSION = "1.0";          // class constant
}
*/




br("---- 2. Constants ----");
br("PI: " . PI);
br("Greeting: " . GREETING);
br();



//////////////////// 3. Output ////////////////////
br("---- 3. Output ----");
br("Hello, $name");                   
br('Hello, ' . $name);               
br();



//////////////////// 4. Data Types ////////////////////
br("---- 4. Data Types ----");
ob_start();
var_dump($name); 
var_dump($age); 
var_dump($isActive);
$dataDump = ob_get_clean();
br(nl2br($dataDump));
br();

// Backticks (template literals like js)
$name = "John";
echo "Hello, $name"; // Or, echo "Hello, {$name}";


// Logical OR (||) and AND (&&) and Nullish Coalescing (??)

$isLoggedIn = true;
$isAdmin = false;

if ($isLoggedIn || $isAdmin) {  }
if ($isLoggedIn && $isAdmin) {  }

$name = null;
echo $name ?? "Guest"; // → "Guest"



// Ternary operator
$username = $input ? $input : "Guest";

$input = "Alice";
$username = $input ?: "Guest";  // → "Alice" (so, if $input exist or truthy then use it else "Guest")

$input = null;
$username = $input ?: "Guest";  // → "Guest" (so, if $input exist then use it esle "Guest")


// $username = $input ? $input : "Guest";


///////// SCOPES (only has function scope) ///////////////


// ✅ PHP does not have block scope — anywhere Meaning: In PHP, variables declared inside: if / for / while / foreach / switch → are accessible outside those blocks, as long as they’re in the same function or global context.

if (true) {
  $x = 10;
}

echo $x; // ✅ Works — $x "leaks" out of the block


// ✅ PHP does have function scope - Variables declared inside functions are not accessible outside.
function greeting() {
  $message = "Hi!";
}

greeting();

echo $message; // ❌ Undefined variable: $message



// Properties (class-level variables)
class User {
  public $name = "John";

  public function printName() {
    echo $this->name;
  }
}

$user = new User();
$user->printName(); // ✅ prints John


// Local variables in methods are still function-scoped
class Example {
  public function run() {
    if (true) {
      $val = 42;
    }
    echo $val; // ✅ Works — no block scope
  }
}



// ❌ Variables declared inside methods are NOT accessible outside
class Something {
  public function doSomething() {
    $temp = "hello";
  }
}

$obj = new Something();
// echo $temp; // ❌ Undefined variable




//////////////////// 5. Control Structures ////////////////////
br("---- 5. Control Structures ----");
if ($age >= 18) {
  br("Adult");
} elseif ($age >= 13) {
  br("Teenager");
} else {
  br("Child");
}

switch ($name) {
  case "John":
    br("Hello John!");
    break;
  default:
    br("Unknown name");
    break;
}
br();



//////////////////// 6. Loops ////////////////////
br("---- 6. Loops ----");

for ($i = 0; $i < 3; $i++) {
  br("For loop Index: $i");
}
br();

$index = 0;
while ($index < 3) {
  br("While loop Index: $index");
  $index++;
}
br();

foreach ($colors as $color) {
  br("Color: $color");
}
br();



//////////////////// 7. Functions ////////////////////
br("---- 7. Functions ----");

function greet($name = "Guest") {
  return "Hi, $name!";
}
br(greet("Alice"));

$square = fn($x) => $x * $x;
br("Square of 5: " . $square(5));
br();


// closure
function outer() {
  $counter = 0;

  return function () use (&$counter) {
    $counter++;
    echo $counter . "\n";
  };
}

$fn = outer();
$fn(); // 1
$fn(); // 2


// Arrow function alternative (PHP 7.4+)
$square = fn($x) => $x * $x;
echo $square(5) . "\n";



// currying
$add = fn($a) => fn($b) => $a + $b;

$add5 = $add(5);
echo $add5(10); // 15

$taxRate = 0.18;
$calculatePriceWithTax = fn($price) => fn() => $price + ($price * $taxRate);

$finalPrice = $calculatePriceWithTax(100); // returns a fn
echo $finalPrice(); // 118



//////////////////// 8. Arrays ////////////////////
br("---- 8. Arrays ----");

$fruits = ["apple", "banana"];
br("First fruit: " . $fruits[0]);

$user = ["name" => "Jane", "age" => 25]; // array of object
br("User name: " . $user["name"]);




br("---- 8. Arrays ----");

// Indexed array
$fruits = ["apple", "banana", "mango"];
br("First fruit: " . $fruits[0]);

// Associative array
$user = ["name" => "Jane", "age" => 25];
br("User name: " . $user["name"]);

foreach ($user as $key => $value) {
  br("$key: $value");
}

// Add item (like push)
array_push($fruits, "orange");
br("After push: " . implode(", ", $fruits));

// Remove last item (like pop)
$lastFruit = array_pop($fruits);
br("Popped: $lastFruit");

// Remove first item (like shift)
$firstFruit = array_shift($fruits);
br("Shifted: $firstFruit");

// Add item at beginning (like unshift)
array_unshift($fruits, "kiwi");
br("After unshift: " . implode(", ", $fruits));

// Check if item exists (like includes)
if (in_array("banana", $fruits)) {
  br("banana is in the fruits");
}

// Get index of item (like indexOf)
$index = array_search("banana", $fruits);
br("Index of banana: " . ($index !== false ? $index : "Not found"));

// Map (array_map)
$squares = array_map(fn($n) => $n * $n, [1, 2, 3, 4]);
br("Squares: " . implode(", ", $squares));

// Filter (array_filter)
$even = array_filter([1, 2, 3, 4], fn($n) => $n % 2 === 0);
br("Even numbers: " . implode(", ", $even));

// Reduce (array_reduce)
$sum = array_reduce([1, 2, 3, 4], fn($carry, $n) => $carry + $n, 0);
br("Sum: $sum");

// Merge (like concat)
$veggies = ["carrot", "potato"];
$groceries = array_merge($fruits, $veggies);
br("Groceries: " . implode(", ", $groceries));

// Sort
sort($fruits); // ascending
br("Sorted fruits: " . implode(", ", $fruits));

// Reverse
$reversed = array_reverse($fruits);
br("Reversed fruits: " . implode(", ", $reversed));

br();

?>


// below code in js would look like as below

// const user = { name: "Jane", age: 25 };

// for (const [key, value] of Object.entries(user)) {
//   console.log(`${key}: ${value}`);
// }

// console.log(); // Just like br() to add a blank line


foreach ($user as $key => $value) {
  br("$key: $value");
}

br();



//////////////////// 9. Superglobals ////////////////////
br("---- 9. Superglobals ----");
br("Current file: " . $_SERVER["PHP_SELF"]);
br();



//////////////////// 10. String Functions ////////////////////
br("---- 10. String Functions ----");
br("Length: " . strlen("Hello"));
br("Uppercase: " . strtoupper("hello"));
br("Replace: " . str_replace("world", "PHP", "Hello world"));
br();
echo strlen("Hello") . "\n";                   // 5
echo strtoupper("hello") . "\n";              // HELLO
echo strtolower("HELLO") . "\n";              // hello
echo ucfirst("hello") . "\n";                 // Hello
echo ucwords("hello world") . "\n";           // Hello World
echo strrev("hello") . "\n";                  // olleh
echo strpos("hello world", "world") . "\n";   // 6
echo str_replace("world", "PHP", "Hello world") . "\n";  // Hello PHP
echo substr("abcdef", 1, 3) . "\n";           // bcd
echo trim("   hello   ") . "\n";              // hello
echo str_repeat("ha", 3) . "\n";              // hahaha
echo explode(" ", "Split this sentence")[1] . "\n"; // this
echo implode("-", ["2025", "04", "05"]) . "\n"; // 2025-04-05




//////////////////// 11. Include Files ////////////////////
// include 'file.php';
// require 'file.php';
br("---- 11. Include Files ----");
br("Include/require statements commented out.");
br();



//////////////////// 12. Classes / OOP ////////////////////
br("---- 12. Classes / OOP ----");

class Person {
  public $name;
  function __construct($name) {
    $this->name = $name;
  }
  function greet() {
    return "Hello, $this->name";
  }
}
$p = new Person("Mike");
br($p->greet());
br();



//////////////////// 13. Null Coalescing & Ternary ////////////////////
br("---- 13. Null Coalescing & Ternary ----");

$username = $_GET['username'] ?? 'Guest';
br("Username: $username");

$score = 85;
br($score > 50 ? "Pass" : "Fail");
br();



//////////////////// 14. Error Handling ////////////////////
br("---- 14. Error Handling ----");

try {
  if (true) {
    throw new Exception("Something went wrong!");
  }
} catch (Exception $e) {
  br("Caught: " . $e->getMessage());
}
br();



//////////////////// 15. Miscellaneous ////////////////////
br("---- 15. Miscellaneous ----");

br("Current date/time: " . date("Y-m-d H:i:s"));
br();
