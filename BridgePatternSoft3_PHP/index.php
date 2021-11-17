<?php

/**
 * La abstracción
 */
abstract class Page
{
    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     *  La abstracción generalmente se inicializa con uno de los objetos de implementación.
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * El patrón Bridge permite reemplazar dinámicamente el objeto Implementación adjunto.
     */
    public function changeRenderer(Renderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * El comportamiento de "vista" permanece abstracto ya que solo puede ser proporcionado por clases de Abstracción Concreta.
     */
    abstract public function view(): string;
}

/**
 * Esta abstracción concreta representa una página simple
 */
class SimplePage extends Page
{
    protected $title;
    protected $content;

    public function __construct(Renderer $renderer, string $title, string $content)
    {
        parent::__construct($renderer);
        $this->title = $title;
        $this->content = $content;
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->renderTitle($this->title),
            $this->renderer->renderTextBlock($this->content),
            $this->renderer->renderFooter()
        ]);
    }
}

/**
 * Esta abstracción concreta representa una página más compleja a diferencia de la
 * inmediatamente anterior.
 */
class ProductPage extends Page
{
    protected $product;

    public function __construct(Renderer $renderer, Product $product)
    {
        parent::__construct($renderer);
        $this->product = $product;
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->renderTitle($this->product->getTitle()),
            $this->renderer->renderTextBlock($this->product->getDescription()),
            $this->renderer->renderImage($this->product->getImage()),
            $this->renderer->renderLink("https://www.starwars.com/databank" . $this->product->getId(), "Enlace a StarWars databank"),
            $this->renderer->renderFooter()
        ]);
    }
}

/**
 * Una clase auxiliar para la clase ProductPage.
 */
class Product
{
    private $id, $title, $description, $image, $price;

    public function __construct(
        string $id,
        string $title,
        string $description,
        string $image,
        float $price
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->price = $price;
    }

    public function getId(): string { return $this->id; }

    public function getTitle(): string { return $this->title; }

    public function getDescription(): string { return $this->description; }

    public function getImage(): string { return $this->image; }

    public function getPrice(): float { return $this->price; }
}


/**
 * La Implementación declara un conjunto de métodos "reales", "internos" y "de plataforma".
 *
 * En este caso, la Implementación enumera los métodos de representación que se pueden utilizar 
 * para componer cualquier página web. Diferentes abstracciones pueden utilizar diferentes 
 * métodos de implementación
 */
interface Renderer
{
    public function renderTitle(string $title): string;

    public function renderTextBlock(string $text): string;

    public function renderImage(string $url): string;

    public function renderLink(string $url, string $title): string;

    public function renderHeader(): string;

    public function renderFooter(): string;

    public function renderParts(array $parts): string;
}

/**
 * Esta implementación concreta convierte una página web en HTML.
 */
class HTMLRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return "<h1>$title</h1>";
    }

    public function renderTextBlock(string $text): string
    {
        return "<div class='text'>$text</div>";
    }

    public function renderImage(string $url): string
    {
        return "<img src='$url'>";
    }

    public function renderLink(string $url, string $title): string
    {
        return "<a href='$url'>$title</a>";
    }

    public function renderHeader(): string
    {
        return "<html><body>";
    }

    public function renderFooter(): string
    {
        return "</body></html>";
    }

    public function renderParts(array $parts): string
    {
        return implode("<br>", $parts);
    }
}

/**
 * Esta implementación concreta representa una página web como cadenas JSON.
 */
class JsonRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return '"title": "' . $title . '"';
    }

    public function renderTextBlock(string $text): string
    {
        return '"text": "' . $text . '"';
    }

    public function renderImage(string $url): string
    {
        return '"img": "' . $url . '"';
    }

    public function renderLink(string $url, string $title): string
    {
        return '"link": {"href": "' . $url . '", "title": "' . $title . '"}';
    }

    public function renderHeader(): string
    {
        return '';
    }

    public function renderFooter(): string
    {
        return '';
    }

    public function renderParts(array $parts): string
    {
        return "{\n" . implode(",<br>", array_filter($parts)) . "<br>}";
    }
}

/**
 * El código de cliente normalmente se ocupa únicamente de los objetos de abstracción.
 * Para este caso particularmente no hacemos uso de el debido a que no es necesario en 
 * nuestro ejemplo de BridgePattern
 */
function clientCode(Page $page)
{
    // ...

    echo $page->view();

    // ...
}

/**
 * El código del cliente se puede ejecutar con cualquier combinación preconfigurada 
 * de Abstracción + Implementación.
 */
$HTMLRenderer = new HTMLRenderer();
$JSONRenderer = new JsonRenderer();

$page = new SimplePage($HTMLRenderer, "Principal", "Bienvenido a nuestro sitio web!");
echo "Esta es una vista simple del contenido HTML de nuestro sitio web:<br>";
clientCode($page);
echo "<br>";

/**
 * La abstracción puede cambiar la implementación vinculada en tiempo de ejecución si es necesario.
 */
$page->changeRenderer($JSONRenderer);
echo "Esta es una vista simple del contenido JSON de nuestro sitio web::<br>";
clientCode($page);
echo "<br>";

$product = new Product("", "Star Wars, Episodio 1",
    "Hace mucho tiempo en una galaxia muy, muy lejana ...",
    "https://i1.wp.com/codigoespagueti.com/wp-content/uploads/2021/05/star-wars-logo.jpg", 39.95);
echo "<br>";

$page = new ProductPage($HTMLRenderer, $product);
echo "Vista HTML de una página de producto con el mismo código del cliente:<br>";
clientCode($page);
echo "<br>";

$page->changeRenderer($JSONRenderer);
echo "Vista JSON de una página de producto con el mismo código del cliente:<br>";
clientCode($page);

?>