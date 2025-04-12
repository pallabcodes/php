// singleton

class DatabaseConnection {
    private static ?DatabaseConnection $instance = null; // ?DatabaseConnection means its value could be null which is what seen here
    private \PDO $connection;

    // Private constructor to prevent direct instantiation from outside of this class
    private function __construct() {
        $this->connection = new \PDO(
            'mysql:host=localhost;dbname=test',
            'username',
            'password'
        );
    }

    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserializing
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}

// Factory pattern

class EntityFactory
{
    public static function create(string $type, array $data): Entity
    {
        return match($type) {
            'customer' => new Customer(
                $data['id'],
                $data['name'],
                $data['email']
            ),
            'product' => new Product(
                $data['id'],
                $data['name'],
                $data['price'],
                $data['stock']
            ),
            'order' => new Order(
                $data['id'],
                $data['customer']
            ),
            default => throw new \InvalidArgumentException("Unknown entity type: {$type}")
        };
    }
}

// Observer pattern

interface Observer
{
    public function update(Subject $subject): void;
}

interface Subject
{
    public function attach(Observer $observer): void;
    public function detach(Observer $observer): void;
    public function notify(): void;
}

class OrderSubject implements Subject
{
    private array $observers = [];
    private Order $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }
    
    public function detach(Observer $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }
    
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
    public function getOrder(): Order
    {
        return $this->order;
    }
}

class OrderLogger implements Observer
{
    public function update(Subject $subject): void
    {
        if ($subject instanceof OrderSubject) {
            $order = $subject->getOrder();
            echo "Order {$order->getId()} has been updated\n";
        }
    }
}

// Strategy pattern

interface PaymentStrategy
{
    public function pay(float $amount): bool;
}

class CreditCardPayment implements PaymentStrategy
{
    private string $cardNumber;
    private string $expiryDate;
    private string $cvv;
    
    public function __construct(string $cardNumber, string $expiryDate, string $cvv)
    {
        $this->cardNumber = $cardNumber;
        $this->expiryDate = $expiryDate;
        $this->cvv = $cvv;
    }
    
    public function pay(float $amount): bool
    {
        // Process credit card payment
        echo "Processing payment of {$amount} with credit card {$this->cardNumber}\n";
        return true;
    }
}

class PayPalPayment implements PaymentStrategy
{
    private string $email;
    private string $password;
    
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    
    public function pay(float $amount): bool
    {
        // Process PayPal payment
        echo "Processing payment of {$amount} with PayPal account {$this->email}\n";
        return true;
    }
}

class PaymentProcessor
{
    private PaymentStrategy $strategy;
    
    public function setStrategy(PaymentStrategy $strategy): self
    {
        $this->strategy = $strategy;
        return $this;
    }
    
    public function processPayment(float $amount): bool
    {
        return $this->strategy->pay($amount);
    }
}

// Decorator pattern
interface Component
{
    public function operation(): string;
}

class ConcreteComponent implements Component
{
    public function operation(): string
    {
        return "ConcreteComponent";
    }
}

abstract class Decorator implements Component
{
    protected Component $component;
    
    public function __construct(Component $component)
    {
        $this->component = $component;
    }
    
    public function operation(): string
    {
        return $this->component->operation();
    }
}

class ConcreteDecoratorA extends Decorator
{
    public function operation(): string
    {
        return "ConcreteDecoratorA(" . parent::operation() . ")";
    }
}

class ConcreteDecoratorB extends Decorator
{
    public function operation(): string
    {
        return "ConcreteDecoratorB(" . parent::operation() . ")";
    }
}


// DEPENDENCY INJECTION

class OrderService
{
    private CustomerRepository $customerRepository;
    private ProductRepository $productRepository;
    private PaymentProcessor $paymentProcessor;
    
    public function __construct(
        CustomerRepository $customerRepository,
        ProductRepository $productRepository,
        PaymentProcessor $paymentProcessor
    ) {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->paymentProcessor = $paymentProcessor;
    }
    
    public function createOrder(string $customerId, array $items, PaymentStrategy $paymentStrategy): Order
    {
        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new \Exception("Customer not found");
        }
        
        $order = new Order(uniqid('order_'), $customer);
        
        foreach ($items as $item) {
            $product = $this->productRepository->findById($item['product_id']);
            if (!$product) {
                throw new \Exception("Product not found");
            }
            
            $order->addItem($product, $item['quantity']);
        }
        
        $this->paymentProcessor->setStrategy($paymentStrategy);
        if (!$this->paymentProcessor->processPayment($order->getTotal())) {
            throw new \Exception("Payment failed");
        }
        
        return $order;
    }
}

/**
 * Repository interfaces
 */
interface CustomerRepository
{
    public function findById(string $id): ?Customer;
    public function save(Customer $customer): bool;
}

interface ProductRepository
{
    public function findById(string $id): ?Product;
    public function save(Product $product): bool;
}

/**
 * Concrete repository implementations
 */
class DatabaseCustomerRepository implements CustomerRepository
{
    private \PDO $connection;
    
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function findById(string $id): ?Customer
    {
        $stmt = $this->connection->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return new Customer(
            $data['id'],
            $data['name'],
            $data['email']
        );
    }
    
    public function save(Customer $customer): bool
    {
        // Implementation
        return true;
    }
}

class DatabaseProductRepository implements ProductRepository
{
    private \PDO $connection;
    
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function findById(string $id): ?Product
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return new Product(
            $data['id'],
            $data['name'],
            $data['price'],
            $data['stock']
        );
    }
    
    public function save(Product $product): bool
    {
        // Implementation
        return true;
    }
}

