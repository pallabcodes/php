<?php

namespace OOP\Core;


// spl_autoload_register(...) = “Hey! Every time a class is used but hasn’t been loaded yet — call this function to try to load it.”

spl_autoload_register(function ($class) {
    // This is a cross-platform way to convert a namespace into a file path: e.g $class = "App\\Utils\\Logger";
    // $file = "App/Utils/Logger.php"; // or App\Utils\Logger.php on Windows

    //  DIRECTORY_SEPARATOR used So this works correctly on Linux, Mac, and Windows Instead of hardcoding / or \

    // N.B: DIRECTORY_SEPARATOR knows which os the php code is running and based on that it uses \ (windows) or / (linux)


    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

class User
{
    private string $id;
    private string $name;
    private string $email;
    private ?string $phone = null; // Nullable type
    private array $roles = [];     // Default value

    // Class constant
    public const STATUS_ACTIVE = 'active';

    // Static property
    private static int $userCount = 0;

    public function __construct(
        string $id,
        string $name,
        string $email,
        ?string $phone = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;

        // increment the static property

        self::$userCount++;
    }

    public function __destruct()
    {
        // Cleanup code
        self::$userCount--;
    }

    // Getters and setters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this; // Method chaining
    }

    // Static method
    public static function getUserCount(): int
    {
        return self::$userCount;
    }

    // Magic methods
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new \InvalidArgumentException("Property {$name} does not exist");
    }

    public function __set(string $name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new \InvalidArgumentException("Property {$name} does not exist");
        }
    }

    public function __call(string $name, array $arguments)
    {
        // Handle dynamic method calls
        if (strpos($name, 'get') === 0) {
            $property = lcfirst(substr($name, 3));
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }
        throw new \BadMethodCallException("Method {$name} does not exist");
    }

    // Type hint with union types (PHP 8.0+)
    public function setRoles(string|array $roles): self
    {
        $this->roles = is_array($roles) ? $roles : [$roles];
        return $this;
    }

    // Return type with union types (PHP 8.0+)
    public function getRoles(): string | array
    {
        return count($this->roles) === 1 ? $this->roles[0] : $this->roles;
    }

    // ?
    public function addRoles(string ...$roles): self
    {
        $this->roles = array_merge($this->roles, $roles);
        return $this;
    }

    // Method with reference parameters
    public function &getReference(): self
    {
        return $this;
    }

    // Method with nullable return type
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    // Method with void return type
    public function clearPhone(): void
    {
        $this->phone = null;
    }

    // Method with never return type (PHP 8.1+)
    public function throwException(): never
    {
        throw new \Exception("This method never returns normally");
    }

    // Method with match expression (PHP 8.0+) with shorthand swithcase (=> means return value when case matched i.e. at left e.g. 'inactive', self::STATUS_ACTIVE)
    public function getStatusText(string $status): string
    {
        return match ($status) {
            self::STATUS_ACTIVE => 'User is active',
            'inactive' => 'User is inactive',
            'pending' => 'User is pending approval',
            default => 'Unknown status'
        };
    }

    // Method with nullsafe operator (PHP 8.0+)
    public function getPhoneCountryCode(): ?string
    {
        return $this->phone?->substring(0, 2);
    }

    // Method with named arguments (PHP 8.0+)
    public static function create(
        string $id,
        string $name,
        string $email,
        ?string $phone = null
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            phone: $phone
        );
    }

    // Method with constructor property promotion (PHP 8.0+)
    public static function createWithPromotion(
        string $id,
        string $name,
        string $email,
        ?string $phone = null
    ): self {
        $instance = new self($id, $name, $email, $phone);
        self::$userCount++;
        return $instance;
    }

    // Method with return type declaration
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'roles' => $this->roles
        ];
    }

    // Method with return type declaration and union types
    public function toJson(): string | false
    {
        return json_encode($this->toArray());
    }

    // Method with return type declaration and intersection types (PHP 8.1+)
    public function getClone(): User&Cloneable
    {
        return clone $this;
    }
}



// Interface for entities that can be persisted, WHAT ?

interface Persistable
{
    public function save(): bool;
    public function delete(): bool;
    public function getId(): string;
}


// Interface for entities that can be cloned, WHAT ?

interface Cloneable
{
    public function __clone();
}

// Interface for entities that can be serialized, WHAT ?

interface Serializable extends \Serializable
{
    // Extends PHP's built-in Serializable interface
}

// Abstract base class for all entities (All entities, which ones ? Did you mean sub-entities basically dervied classes from this Entity or WHAT ?)

abstract class Entity implements Persistable, Cloneable
{
    protected string $id;
    protected \DateTime $createdAt; // why used this \ here ? What does it even mean ?
    protected \DateTime $updatedAt; // why used this \ here ? What does it even mean ?

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Abstract method that must be implemented by subclasses
    abstract public function validate(): bool;

    // Concrete method that can be used by all subclasses
    public function getId(): string
    {
        return $this->id;
    }

    // Method with final keyword to prevent overriding
    final public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    // Method that can be overridden by subclasses
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    // Implementation of Persistable interface
    public function save(): bool
    {
        $this->updatedAt = new \DateTime();
        return $this->validate();
    }

    public function delete(): bool
    {
        // Implementation
        return true;
    }

    // Implementation of Cloneable interface
    public function __clone()
    {
        $this->id = uniqid('clone_');
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
}

// Concrete class that extends an abstract class

class Customer extends Entity
{
    private string $name;
    private string $email;
    private array $orders = [];

    public function __construct(string $id, string $name, string $email)
    {
        // call the base classes constructor to provide argument either trhough constructor parameter or hardcoded

        parent::__construct($id);
        $this->name = $name;
        $this->email = $email;
    }

    // Implementation of abstract method
    public function validate(): bool
    {
        return !empty($this->name) && filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    // Method overriding
    public function getUpdatedAt(): \DateTime
    {
        // Call parent method and modify result
        $date = parent::getUpdatedAt();
        $date->modify('+1 second');
        return $date;
    }

    // New methods specific to Customer
    public function addOrder(Order $order): self
    {
        $this->orders[] = $order;
        return $this;
    }

    public function getOrders(): array
    {
        return $this->orders;
    }
}

// Another concrete class that extends the same abstract class

class Product extends Entity
{
    private string $name;
    private float $price;
    private int $stock;

    public function __construct(string $id, string $name, float $price, int $stock)
    {
        parent::__construct($id);
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
    }

    // Implementation of abstract method
    public function validate(): bool
    {
        return !empty($this->name) && $this->price > 0 && $this->stock >= 0;
    }

    // New methods specific to Product
    public function decreaseStock(int $amount = 1): bool
    {
        if ($this->stock >= $amount) {
            $this->stock -= $amount;
            return true;
        }
        return false;
    }

    public function increaseStock(int $amount = 1): self
    {
        $this->stock += $amount;
        return $this;
    }
}

// Trait for logging functionality

trait Loggable
{
    private array $logs = [];

    public function log(string $message, string $level = 'info'): void
    {
        $this->logs[] = [
            'message' => $message,
            'level' => $level,
            'timestamp' => new \DateTime()
        ];
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}

// Trait for timestamp functionality

trait Timestampable
{
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function setCreatedAt(\DateTime $date): self
    {
        $this->createdAt = $date;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $date): self
    {
        $this->updatedAt = $date;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}

// Class that uses multiple traits

class Order extends Entity
{
    use Loggable, Timestampable;

    private Customer $customer;
    private array $items = [];
    private float $total = 0.0;

    public function __construct(string $id, Customer $customer)
    {
        parent::__construct($id);
        $this->customer = $customer;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Implementation of abstract method
    public function validate(): bool
    {
        return !empty($this->items);
    }

    // New methods specific to Order
    public function addItem(Product $product, int $quantity = 1): self
    {
        $this->items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'price' => $product->getPrice()
        ];

        $this->total += $product->getPrice() * $quantity;
        $this->log("Added {$quantity} of {$product->getName()} to order");

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    // Method that conflicts with trait method
    public function getUpdatedAt(): \DateTime
    {
        // Call trait method
        return parent::getUpdatedAt();
    }
}
