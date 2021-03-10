<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

final class QueryStorageBank
{
	private array $data = [];
	
	private array $container = [];
	
	private $db;
	
	public function __construct ( $database = null )
	{
		$this -> db = $database;
		
		register_shutdown_function ( [ $this, 'giveAway' ] );
	}
	
	public function set( string $name, /* mixed */ $mixed ): void
	{
		$this -> data[$name][] = $mixed;
	}
	
	public function giveAway(): void
	{
		foreach ( $this -> container AS $name => $callable )
		{
			$callable( $this -> db, $this -> data[$name] ?? null );
			
			unset ( $this -> data[$name] );
		}
		
		$this -> container = [];
	}
}
