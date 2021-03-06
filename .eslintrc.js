module.exports = {
    "env": {
        "browser": true,
        "es2021": true,
        "node": true,
    },
    "extends": [
        "airbnb"
    ],
    "parserOptions": {
        "ecmaVersion": 12,
        "sourceType": "module"
    },
    "rules": {
        "import/prefer-default-export": "off",
        "import/extensions": "off",
        "quotes": [2, "double", "avoid-escape"],
        'no-plusplus': [2, { allowForLoopAfterthoughts: true }]
    }
};
