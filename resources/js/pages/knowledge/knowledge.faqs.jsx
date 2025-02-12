import React from "react";
import { Collapse } from "antd";

const { Panel } = Collapse;

const Faqs = ({ items, keyword }) => {
    return (
        <Collapse>
            {items
                .filter((item) =>
                    item.answer.toLowerCase().includes(keyword.toLowerCase())
                )
                .map(({ question, answer, id }) => (
                    <Panel header={question} key={id}>
                        {answer}
                    </Panel>
                ))}
        </Collapse>
    );
};

export default Faqs;
