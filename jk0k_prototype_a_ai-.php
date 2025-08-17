<?php

class GamePrototypeParser {
    private $gameData;
    private $aiEngine;

    public function __construct($gameData) {
        $this->gameData = $gameData;
        $this->aiEngine = new AIEngine();
    }

    public function parse() {
        $parsedData = array();
        foreach ($this->gameData as $level) {
            $parsedLevel = array();
            foreach ($level as $entity) {
                $entityType = $this->aiEngine->identifyEntityType($entity);
                switch ($entityType) {
                    case 'obstacle':
                        $parsedLevel[] = new Obstacle($entity);
                        break;
                    case 'player':
                        $parsedLevel[] = new Player($entity);
                        break;
                    case 'enemy':
                        $parsedLevel[] = new Enemy($entity);
                        break;
                    default:
                        throw new Exception("Unknown entity type: $entityType");
                }
            }
            $parsedData[] = $parsedLevel;
        }
        return $parsedData;
    }
}

class AIEngine {
    private $entityTypes = array(
        'obstacle' => '/^obstacle_/',
        'player' => '/^player_/',
        'enemy' => '/^enemy_/'
    );

    public function identifyEntityType($entity) {
        foreach ($this->entityTypes as $type => $pattern) {
            if (preg_match($pattern, $entity)) {
                return $type;
            }
        }
        return null;
    }
}

class Obstacle {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getPosition() {
        return $this->data['position'];
    }
}

class Player {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getPosition() {
        return $this->data['position'];
    }

    public function getScore() {
        return $this->data['score'];
    }
}

class Enemy {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getPosition() {
        return $this->data['position'];
    }

    public function getHealth() {
        return $this->data['health'];
    }
}

$gameData = array(
    array(
        'obstacle_1' => array('position' => '10,10'),
        'player_1' => array('position' => '20,20', 'score' => 100),
        'enemy_1' => array('position' => '30,30', 'health' => 50)
    ),
    array(
        'obstacle_2' => array('position' => '40,40'),
        'enemy_2' => array('position' => '50,50', 'health' => 75)
    )
);

$parser = new GamePrototypeParser($gameData);
$parsedData = $parser->parse();

print_r($parsedData);

?>