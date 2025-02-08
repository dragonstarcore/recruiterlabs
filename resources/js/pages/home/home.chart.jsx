import ApexCharts from "react-apexcharts";

const ChartContainer = ({
    chartData,
    title,
    seriesName,
    chartType,
    YaxisTitle,
    color,
}) => {
    console.log(chartData);

    const formatData = (data) => {
        if (!data) return;
        const names = [];
        const values = [];

        // Ensure data exists and contains 'name' and 'y'
        data.forEach((item) => {
            if (item && item.name && item.y !== undefined) {
                names.push(item.name);
                values.push(item.y);
            }
        });

        return { names, values };
    };
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
                    categories: formatData(chartData).names,
                },
                yaxis: {
                    title: {
                        text: YaxisTitle,
                    },
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
                    name: seriesName,
                    data: formatData(chartData).values,
                },
            ]}
            type={chartType}
            height={350}
        />
    );
};
export default ChartContainer;
