-- Base de datos dedicada para la suite de pruebas (QA), separada de la de desarrollo.
SELECT 'CREATE DATABASE camposanto_testing OWNER camposanto'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'camposanto_testing')\gexec
