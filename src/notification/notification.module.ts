import { DynamicModule,Module } from "@nestjs/common";
import { NotificationService } from "./notification.service";
import { NotificationOptionModule } from "./notification.interface";

@Module({})
export class NotificationModule{
    static register(options:NotificationOptionModule): DynamicModule{
        return{
            module: NotificationModule,
            providers:[
                {
                    provide: 'NOTIFICATION_OPTIONS',
                    useValue: options,
                },
                NotificationService,
            ],
            exports: [NotificationService],
        }
    }
}