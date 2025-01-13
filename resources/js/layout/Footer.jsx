import { Layout, Row, Col } from "antd";
import { HeartFilled } from "@ant-design/icons";

function Footer() {
    const { Footer: AntFooter } = Layout;

    return (
        <AntFooter style={{ background: "#fafafa" }}>
            <Row className="just">
                <Col xs={24} md={12} lg={12}>
                    <div className="copyright">
                        © 2025, made with
                        {<HeartFilled />} by
                        <a
                            href="https://www.peopleperhour.com/freelancer/technology-programming/dmytro-maloshtan-software-engineer-zamnyjnw"
                            className="font-weight-bold"
                            target="_blank"
                        >
                            Dmytro
                        </a>
                    </div>
                </Col>
                <Col xs={24} md={12} lg={12}>
                    <div className="footer-menu">
                        <ul>
                            <li className="nav-item">
                                <a
                                    href="https://recruiterlabs.co/"
                                    className="nav-link text-muted"
                                    target="_blank"
                                >
                                    RecruiterLabs
                                </a>
                            </li>
                        </ul>
                    </div>
                </Col>
            </Row>
        </AntFooter>
    );
}

export default Footer;
