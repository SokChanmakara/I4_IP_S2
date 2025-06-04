import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Task } from './task.entity';
import { NotificationService } from 'src/notification/notification.service';

@Injectable()
export class TaskService {
  constructor(
    @InjectRepository(Task)
    private readonly taskRepo: Repository<Task>,
    private readonly notifier:NotificationService
  ) {}

  create(task:any){
    this.notifier.notify(`Task "${task.title}" created.`)
  }

  async getTask(id: number) {
    const task = await this.taskRepo.findOne({ where: { id }, relations: ['user'] });
    if (!task) {
      throw new NotFoundException(`Task with ID ${id} not found`);
    }
    return task;
  }

  async getAllTasks() {
    return await this.taskRepo.find({ relations: ['user'] });
  }

  async createTask(taskData: Partial<Task>) {
    const task = this.taskRepo.create({
      ...taskData,
      createdAt: new Date(),
    });
    return await this.taskRepo.save(task);
  }

  async updateTask(id: number, updateData: Partial<Task>) {
    const task = await this.getTask(id); 
    Object.assign(task, updateData);
    return await this.taskRepo.save(task);
  }

  async deleteTask(id: number) {
    const result = await this.taskRepo.delete(id);
    if (result.affected === 0) {
      throw new NotFoundException(`Task with ID ${id} not found`);
    }
    return { message: 'Task deleted successfully' };
  }
}
