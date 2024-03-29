<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection\ListCollection;
use AstrobinWs\Response\DTO\Collection\ListImages;
use AstrobinWs\Response\DTO\Item\Collection;
use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Response\Iterators\CollectionIterator;
use AstrobinWs\Services\GetCollection;
use AstrobinWs\Services\WsAstrobinTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class GetCollectionTest extends TestCase
{
    public ?GetCollection $astrobinWs = null;

    public ?GetCollection $badAstrobinWs = null;

    protected function setUp(): void
    {
        $this->badAstrobinWs = new GetCollection(null, null);
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetCollection($astrobinKey, $astrobinSecret);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetEndPoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);

        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetCollection::END_POINT, $endPoint);
    }

    public function testGetObjectEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getObjectEntity');
        $method->setAccessible(true);

        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(Collection::class, $response);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetCollectionEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getCollectionEntity');
        $method->setAccessible(true);

        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(ListCollection::class, $response);
    }

    /**
     * @throws WsResponseException
     * @throws WsException
     * @throws \JsonException
     */
    public function testNullableKeys(): void
    {
        $badResponse = $this->badAstrobinWs->getById('1');
        $this->assertInstanceOf(AstrobinError::class, $badResponse);
        $this->assertEquals(WsException::KEYS_ERROR, $badResponse->getMessage());
    }

    /**
     * @throws \JsonException
     * @throws WsResponseException
     */
    public function testGetById(): void
    {
        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $this->astrobinWs->getById(null);

        $response = $this->astrobinWs->getById('a');
        $this->assertInstanceOf(AstrobinError::class, $response);

        $id = '25';
        $reponse = $this->astrobinWs->getById($id);
        $this->assertInstanceOf(Collection::class, $reponse);
        $this->assertEquals($id, $response->id);
        $this->assertInstanceOf(ListImages::class, $response->images);
        foreach ($response->images as $image) {
            $this->assertInstanceOf(Image::class, $image);
        }
        $this->assertIsInt($response->images->count);
    }

    /**
     * @throws \JsonException
     */
    public function testGetListCollectionByUser(): void
    {
        $limitTooHigh = 999999;
        $response = $this->astrobinWs->getListCollectionByUser('siovene', $limitTooHigh);
        $this->assertNull($response);

        $response = $this->astrobinWs->getListCollectionByUser('siovene', 2);
        $this->assertInstanceOf(ListCollection::class, $response);
        $this->assertIsIterable($response->listCollection);
        $this->assertInstanceOf(CollectionIterator::class, $response->getIterator());

    }

    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function testAddImagesInCollection(): void
    {
        $collection = $this->astrobinWs->getById('25');
        $reflectionClass = new ReflectionClass(GetCollection::class);
        $reflectionMethod = $reflectionClass->getMethod('getImagesFromResource');
        $reflectionMethod->setAccessible(true);

        // Override
        $collection->images = [
            "/api/v1/image/131428",
            "/api/v1/image/108615",
            "/api/v1/image/64901",
            "/api/v1/image/63984",
            "/api/v1/image/51197",
            "/api/v1/image/50888",
            "/api/v1/image/48807",
            "/api/v1/image/48433",
            "/api/v1/image/46870",
            "/api/v1/image/28489",
        ];
        $nbItems = count($collection->images);

        $collection = $reflectionMethod->invoke($this->astrobinWs, $collection); // $this->astrobinWs->getImagesFromResource($collection);
        $this->assertCount($nbItems, $collection->images);
        foreach ($collection->images as $image) {
            $this->assertInstanceOf(AstrobinResponse::class, $image);
        }
    }


    protected function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
