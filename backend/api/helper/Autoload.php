<?php

// register autoload
function __autoload($class) {
	if(strrpos($class, "Controller") > 1) {
		require("./controller/" . $class . ".php");
	}
	else if(strrpos($class, "DAO") > 1) {
		require("./dao/" . $class . ".php");
	}
	else if(strrpos($class, "Helper") > 1) {
		require("./helper/" . $class . ".php");
	}
}
