import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Task } from './task.entity'; 
import { TasksController } from './task.controller';
import { TaskService } from './task.service';
import { UserModule } from '../user/user.module'; 
import { NotificationModule } from 'src/notification/notification.module';

@Module({
  imports: [
    NotificationModule.register({type: 'log'}),
    TypeOrmModule.forFeature([Task]), 
    UserModule,
  ],
  providers: [TaskService],
  controllers: [TasksController],
})
export class TaskModule {}
