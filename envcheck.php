<?php
echo "<pre>";
var_dump(
    getenv("DB_HOST"),
    getenv("DB_PORT"),
    getenv("DB_USER"),
    getenv("DB_PASS") ? 'HAS_PASS' : 'NO_PASS',
    getenv("DB_NAME")
);
