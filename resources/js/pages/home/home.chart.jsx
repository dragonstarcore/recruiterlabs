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

    const formatData = (data = []) => {
        if (!data) return;
        const names = [];
        const values = [];
        console.log(data);
        // Ensure data exists and contains 'name' and 'y'
        Object.values(data).forEach((item) => {
            if (item && item.name && item.y !== undefined) {
                names.push(item.name);
                if (typeof item.y == "number") values.push(item.y.toFixed(2));
                else values.push(item.y);
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
                    categories: formatData(chartData)?.names,
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
