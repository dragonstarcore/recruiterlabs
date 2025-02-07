import ApexCharts from "react-apexcharts";

const ChartContainer = ({ chartData, title, chartType, chartId, color }) => {
    return (
        <ApexCharts
            options={{
                chart: {
                    type: chartType,
                    zoom: { enabled: true },
                },
                colors: [color],
                title: {
                    text: title,
                },
                xaxis: {
                    categories: chartData.names,
                },
                tooltip: {
                    stickOnContact: true,
                },
                legend: {
                    enabled: false,
                },
            }}
            series={[
                {
                    name: "Count",
                    data: chartData.values,
                },
            ]}
            type={chartType}
            height={350}
        />
    );
};
export default ChartContainer;
