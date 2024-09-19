<?php

namespace Chernoff\LvivItTestAssignment\Tests\Controller\MainController;

use Chernoff\LvivItTestAssignment\Controller\MainController;
use Chernoff\LvivItTestAssignment\Service\FileManager\FileManagerService;
use Chernoff\LvivItTestAssignment\Service\Preprocessor\PreprocessorService;
use Chernoff\LvivItTestAssignment\Service\StreamDataReader\StreamDataReaderService;
use Chernoff\LvivItTestAssignment\Tests\ContainerAwareTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UploadTest extends ContainerAwareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testUploadActionReturnsErrorWithInvalidData(string $body, array $expectedResponse): void
    {
        $reader = $this->mockStreamDataReader($body);
        $response = $this->configureController(reader: $reader)->upload('filename');

        $this->assertSame($expectedResponse, $response);
    }

    public static function invalidDataProvider(): array
    {
        return [
            'empty body' => [
                'body' => '',
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'invalid body' => [
                'body' => 'hello',
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'missing source' => [
                'body' => json_encode([
                    'payload' => [
                        'email' => 'some@email.net',
                        'some' => 'important',
                        'data' => 'here',
                    ],
                ]),
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'empty source' => [
                'body' => json_encode([
                    'source' => '',
                    'payload' => [
                        'email' => 'some@email.net',
                        'some' => 'important',
                        'data' => 'here',
                    ],
                ]),
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'missing payload' => [
                'body' => json_encode([
                    'source' => 'source',
                ]),
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'empty payload' => [
                'body' => json_encode([
                    'source' => 'source',
                    'payload' => [],
                ]),
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"source" and "payload" are required',
                ],
            ],
            'payload is not an array' => [
                'body' => json_encode([
                    'source' => 'source',
                    'payload' => 'whoops',
                ]),
                'expectedResponse' => [
                    'status' => 400,
                    'error' => '"payload" should be an array',
                ],
            ],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testUploadActionReturnsSuccessWithValidData(string $body, array $expectedResponse): void
    {
        $reader = $this->mockStreamDataReader($body);
        $response = $this->configureController(reader: $reader)->upload('new_file');

        $this->assertSame($expectedResponse, $response);
    }

    public static function validDataProvider(): array
    {
        return [
            [
                'body' => json_encode([
                    'source' => 'source',
                    'payload' => [
                        'email' => 'some@email.net',
                        'some' => 'important',
                        'data' => 'here',
                    ],
                ]),
                'expectedResponse' => [
                    'status' => 200,
                    'is_successful' => true,
                ],
            ],
        ];
    }

    /**
     * @depends
     */
    public function testUploadActionRemovesSensitiveData(): void
    {
        $response = $this->configureController()->download('new_file');

        $this->assertSame([
            'source' => 'source',
            'payload' => [
                'email' => '_SENSITIVE_DATA_REMOVED_',
                'some' => 'important',
                'data' => 'here',
            ],
        ], $response);
    }

    protected function configureController(
        ?FileManagerService $fileManager = null,
        ?PreprocessorService $preprocessor = null,
        ?StreamDataReaderService $reader = null,
    ): MainController {
        return new MainController(
            $fileManager ?? $this->container[FileManagerService::class],
            $preprocessor ?? $this->container[PreprocessorService::class],
            $reader ?? $this->container[StreamDataReaderService::class],
        );
    }

    protected function mockStreamDataReader(string $content): MockObject|StreamDataReaderService
    {
        $mock = $this->createMock(StreamDataReaderService::class);
        $mock->method('read')->willReturn($content);

        return $mock;
    }
}
