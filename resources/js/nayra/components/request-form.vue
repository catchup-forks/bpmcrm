<template>
    <div class="card">
        <div class="card-header">
            Form ID: <span class="badge badge-info">{{formId}}</span>
        </div>
        <div class="card-body">
            <h5 class="card-title">Request</h5>
            <form>
                <div class="form-group">
                    <label for="startDate">Start date</label>
                    <input id ="startDate" aria-describedby="startDateHelp" type="datetime" class="form-control" v-model="localStartDate" placeholder="Start Date">
                </div>
                <div class="form-group">
                    <label for="endDate">End date</label>
                    <input id ="endDate" aria-describedby="endDateHelp" type="datetime" class="form-control" v-model="localEndDate" placeholder="End Date">
                </div>
                <div class="form-group">
                    <label for="endDate">Reason</label>
                    <textarea id ="reason" class="form-control" v-model="localReason" placeholder="Reason" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-primary" @click="submit">Continue</button>
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
                localStartDate: this.startDate,
                localEndDate: this.endDate,
                localReason: this.reason,
                instances: [],
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
                    startDate: this.localStartDate,
                    endDate: this.localEndDate,
                    reason: this.localReason
                })
                .then((response) => {
                })
            }
        }

    }

</script>

<style lang="scss" scoped>

</style>