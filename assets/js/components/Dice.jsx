import React from "react";

export default function Dice ({number, color}) {
    const style = {
        "marginRight": "1%"
    }

    if (number === 1) {
        number = "one"
    } else if (number === 2) {
        number = "two"
    } else if (number === 3) {
        number = "three"
    } else if (number === 4) {
        number = "four"
    } else if (number === 5) {
        number = "five"
    } else if (number === 6) {
        number = "six"
    }

    return <span data-title-test={"dice-" + number + "-" + color}>
        <i className={"fas fa-dice-" + number + " fa-2x dice-color-" + color} style={style}></i>
    </span>
}