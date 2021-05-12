<?php
namespace LumiteStudios\Action\Tests;

use Jchook\AssertThrows\AssertThrows;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	use AssertThrows;
	use WithFaker;
}
