<template>
    <div class="card">
        <div class="card-header">
            Form ID: <span class="badge badge-info">{{formId}}</span>
        </div>
        <div class="card-body">
            <h5 class="card-title">Approve</h5>
            <form>
                <legend>Request</legend>
                <div class="form-group">
                    <label for="startDate">Start date</label>
                    <input disabled id ="startDate" aria-describedby="startDateHelp" type="datetime" class="form-control" v-model="startDate" placeholder="Start Date">
                </div>
                <div class="form-group">
                    <label for="endDate">End date</label>
                    <input disabled id ="endDate" aria-describedby="endDateHelp" type="datetime" class="form-control" v-model="endDate" placeholder="End Date">
                </div>
                <div class="form-group">
                    <label for="endDate">Reason</label>
                    <textarea disabled id ="reason" class="form-control" v-model="reason" placeholder="Reason" rows="3"></textarea>
                </div>
                <legend>Supervisor approval?</legend>
                <div class="form-group">
                    <i class="fa fa-check"></i>
                </div>
                <legend>Human Resources</legend>
                <button type="button" class="btn btn-primary" @click="submit">Complete</button>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        props: [
            'processId',
            'instanceId',
            'tokenId',
            'formId',

            'startDate',
            'endDate',
            'reason',
        ],
        data() {
            return {
                approved: '0',
                tokens: [],
            };
        },
        mounted() {
            // Listen for our notifications
            let userId = document.head.querySelector('meta[name="user-id"]').content;
            Echo.private(`app.Model.User.${userId}`)
                .notification((token) => {
                    ProcessMaker.pushNotification(token);
                });
        },
        methods: {
            submit() {
                ProcessMaker.apiClient.post(
                        'processes/' + this.processId +
                        '/instances/' + this.instanceId +
                        '/tokens/' + this.tokenId +
                        '/complete', 
                {
                })
                .then((response) => {
                })
            }
        }

    }

</script>

<style lang="scss" scoped>

</style>