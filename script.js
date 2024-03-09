const AWS = require('aws-sdk');
const ses = new AWS.SES();

exports.handler = async (event) => {
    const formData = event.body;
    const emailParams = {
        Destination: {
            ToAddresses: ['hr@utkalb2b.in']
        },
        Message: {
            Body: {
                Text: {
                    Data: `New job application received:\n\n${formData}`
                }
            },
            Subject: {
                Data: 'New Job Application'
            }
        },
        Source: 'noreply@yourdomain.com'
    };
    try {
        const data = await ses.sendEmail(emailParams).promise();
        return {
            statusCode: 200,
            body: 'Your application has been submitted successfully.'
        };
    } catch (err) {
        console.error('Error sending email:', err);
        return {
            statusCode: 500,
            body: 'Failed to submit your application. Please try again later.'
        };
    }
};
