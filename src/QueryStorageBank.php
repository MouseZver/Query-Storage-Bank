<?php

declare ( strict_types = 1 );

namespace Nouvu\Database;

final class QueryStorageBank
{
	private array $data;
	
	private array $container = [];
	
	public function __construct ( private $db = null )
	{
		register_shutdown_function ( [ $this, 'giveAway' ] );
	}
	
	public function setEvent( string $name, callable $callable ): void
	{
		$this -> container[$name] = $callable;
	}
	
	public function save( string $name, mixed $mixed ): void
	{
		$this -> data[$name][] = $mixed;
	}
	
	public function giveAway(): void
	{
		foreach ( $this -> container AS $name => $callable )
		{
			if ( isset ( $this -> data[$name] ) )
			{
				$callable( $this -> db, $this -> data[$name] );
				
				unset ( $this -> data[$name] );
			}
		}
		
		$this -> container = [];
	}
}
